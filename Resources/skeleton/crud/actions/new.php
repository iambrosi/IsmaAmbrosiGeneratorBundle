
    /**
     * Displays a form to create a new {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/new", name="{{ route_name_prefix }}_new")
     * @Template()
     *
     * @return array
{% else %}
     * @return \Symfony\Component\HttpFoundation\Response
{% endif %}
     */
    public function newAction()
    {
        $document = new {{ document_class }}();
        $form = $this->createForm(new {{ document_class }}Type(), $document);

{% if 'annotation' == format %}
        return array(
            'document' => $document,
            'form'     => $form->createView()
        );
{% else %}
        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:new.html.twig', array(
            'document' => $document,
            'form'     => $form->createView()
        ));
{% endif %}
    }
