<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;

class TaskType Extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('title', TextType::class, array(
            'label' => 'TItulo'
        ));
        $builder->add('content', TextareaType::class, array(
            'label' => 'Descricion tarea'
        ));
        $builder->add('priority', ChoiceType::class, array(
            'label' => 'Prioridad',
            'choices' => array(
                'Alta' => 'high',
                'Media' => 'medium',
                'Baja' => 'low'
            )
        ));
        $builder->add('hours', TextType::class, array(
            'label' => 'Horas'
        ));
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Agregar'
        ));
    }

}