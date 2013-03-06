<?php

namespace {{ namespace }}\Controller{{ controller_namespace ? '\\' ~ controller_namespace : '' }};

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
{% if 'annotation' == format -%}
{% if 'new' in actions or 'edit' in actions or 'delete' in actions -%}
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
{% endif %}
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
{% endif %}
{% if 'new' in actions or 'edit' in actions %}
use {{ namespace }}\Document\{{ document }};
use {{ namespace }}\Form\{{ document }}Type;
{% endif %}

/**
 * {{ document }} controller.
{% if 'annotation' == format %}
 *
 * @Route("/{{ route_prefix }}")
{% endif %}
 */
class {{ document_class }}Controller extends Controller
{

    {%- if 'index' in actions %}
        {%- include 'actions/index.php' %}
    {%- endif %}

    {%- if 'show' in actions %}
        {%- include 'actions/show.php' %}
    {%- endif %}

    {%- if 'new' in actions %}
        {%- include 'actions/new.php' %}
        {%- include 'actions/create.php' %}
    {%- endif %}

    {%- if 'edit' in actions %}
        {%- include 'actions/edit.php' %}
        {%- include 'actions/update.php' %}
    {%- endif %}

    {%- if 'delete' in actions %}
        {%- include 'actions/delete.php' %}
    {%- endif %}

    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    private function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }
}
