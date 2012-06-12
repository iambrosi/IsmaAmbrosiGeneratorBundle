<?php

namespace {{ namespace }}\Form{{ document_namespace ? '\\' ~ document_namespace : '' }};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class {{ form_class }} extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        {%- for field in fields %}

            ->add('{{ field }}')

        {%- endfor %}

        ;
    }

    public function getName()
    {
        return '{{ form_type_name }}';
    }
}
