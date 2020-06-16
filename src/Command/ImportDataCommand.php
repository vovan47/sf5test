<?php

namespace App\Command;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends Command
{
    const TYPE_PRODUCT = 'product';
    const TYPE_CATEGORY = 'category';

    /**
     * @var OutputInterface
     */
    protected $output;

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em,
        CategoryService $categoryService,
        ProductService $productService
    )
    {
        $this->em = $em;
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:import-data')
            ->setDescription('Imports data from JSON file')
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Entity type: product or category'
            )
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Path to file with JSON data'
            );
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $type = $input->getOption('type');

        if (!in_array($type, [self::TYPE_PRODUCT, self::TYPE_CATEGORY])) {
            throw new \InvalidArgumentException('Wrong type option specified');
        }

        if ($filename = $input->getOption('file')) {
            $contents = file_get_contents($filename);
        } else if (0 === ftell(STDIN)) {
            $contents = '';
            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }
        } else {
            throw new \RuntimeException("Please provide a filename or pipe JSON content to STDIN.");
        }

        $output->writeln('Starting import');
        if ($type === self::TYPE_CATEGORY) {
            $count = $this->importCategories($contents);
        } else if ($type === self::TYPE_PRODUCT) {
            $count = $this->importProducts($contents);
        }
        $output->writeln(sprintf('Imported %d entities of type "%s"', $count, $type));
        $output->writeln('Finished import');
        return 0;
    }

    /**
     * @param string $content
     * @return int
     * @throws \Exception
     */
    protected function importCategories($content)
    {
        $handler = $this->categoryService;
        $array = json_decode($content, true);
        if (!is_array($array)) {
            throw new \Exception('Invalid JSON');
        }
        $count = 0;
        foreach ($array as $key => $row) {
            try {
                $form = $handler->createForm(null, [
                    'http_method' => 'POST',
                ]);
                $row['eid'] = $row['eId'];
                $form->submit($row);
                if (!$handler->isPostValid($form)) {
                    $formErrors = (string) $form->getErrors(true, false);
                    throw new \Exception('Validation error: ' . $formErrors);
                }

                $category = $handler->persist($form);
                $handler->flush();

                $count++;
            } catch (\Exception $e) {
                $this->output->writeln(sprintf('Error on row %d: %s', $key + 1, $e->getMessage()));
            }
        }
        return $count;
    }

    /**
     * @param string $content
     * @return int
     * @throws \Exception
     */
    protected function importProducts($content)
    {
        $handler = $this->productService;
        $categoryService = $this->categoryService;
        $array = json_decode($content, true);
        if (!is_array($array)) {
            throw new \Exception('Invalid JSON');
        }
        $count = 0;
        foreach ($array as $key => $row) {
            try {
                $form = $handler->createForm(null, [
                    'http_method' => 'POST',
                ]);
                $row['eid'] = $row['eId'];
                $categoryEids = $row['categoriesEId'] ?? $row['categoryEId'] ?? null;
                if (!empty($categoryEids)) {
                    $result = $categoryService->getRepository()->findByEids($categoryEids);
                    $categoryIds = array_column($result, "id");
                }
                $row['categories'] = $categoryIds;

                $form->submit($row);
                if (!$handler->isPostValid($form)) {
                    $formErrors = (string) $form->getErrors(true, false);
                    throw new \Exception('Validation error: ' . $formErrors);
                }

                $product = $handler->persist($form);
                $handler->flush();

                $count++;
            } catch (\Exception $e) {
                $this->output->writeln(sprintf('Error on row %d: %s', $key + 1, $e->getMessage()));
            }
        }
        return $count;
    }
}