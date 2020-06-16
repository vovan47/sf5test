<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
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
            ->add('eid', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'http_method' => 'POST'
        ]);

        $resolver->setRequired('http_method');
    }
}