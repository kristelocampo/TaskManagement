<?php

namespace App\Form;

use App\Entity\Priority;
use App\Entity\Projects;
use App\Entity\Status;
use App\Entity\Tasks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('due_date')
            ->add('start_date')
            ->add('project_id', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
                'choice_value' => 'id',
            ])
            ->add('status_id', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'status',
                'choice_value' => 'id',
            ])
            ->add('priority_id', EntityType::class, [
                'class' => Priority::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ]);


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
