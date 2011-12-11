
    /**
     * Lists all {{ document }} documents.
     *
{% if 'annotation' == format %}
     * @Route("/", name="{{ route_name_prefix }}")
     * @Template()
{% endif %}
     */
    public function indexAction()
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $documents = $dm->getRepository('{{ bundle }}:{{ document }}')->findAll();

{% if 'annotation' == format %}
        return array('documents' => $documents);
{% else %}
        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:index.html.twig', array(
            'documents' => $documents
        ));
{% endif %}
    }
