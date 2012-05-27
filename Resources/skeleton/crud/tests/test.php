<?php

namespace {{ namespace }}\Tests\Controller{{ controller_namespace ? '\\' ~ controller_namespace : '' }};

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class {{ controller_class }}ControllerTest extends WebTestCase
{
    /*

{%- if 'new' in actions %}
    {%- include 'tests/others/full_scenario.php' -%}
{%- else %}
    {%- include 'tests/others/short_scenario.php' -%}
{%- endif %}

    */
}
