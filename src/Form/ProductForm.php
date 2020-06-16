<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['http_method']) {
            case 'POST':
                $this->buildPostForm($builder);
                break;
            case 'PATCH':
                $this->buildPatchForm($builder);
                break;
        }
    }

    protected function buildPatchForm(FormBuilderInterface $builder)
    {
        $this->addCommonFields($builder);
    }

    protected function buildPostForm(FormBuilderInterface $builder)
    {
        $this->addCommonFields($builder);
    }

    protected function addCommonFields(FormBuilderInterface $builder)
    {
        $builder
            ->add('title')
            ->add('categories', EntityType::class, [
                'class' => Category::class
            ])
            ->add('price', FloatType::class)
            ->add('eid', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
            'allow_extra_fields' => false,
            'http_method' => 'POST'
        ]);

        $resolver->setRequired('http_method');
    }
}