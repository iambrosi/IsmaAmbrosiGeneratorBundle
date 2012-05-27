
    /**
     * Lists all {{ document }} documents.
     *
{% if 'annotation' == format %}
     * @Route("/", name="{{ route_name_prefix }}")
     * @Template()
     *
     * @return array
{% else %}
     * @return \Symfony\Component\HttpFoundation\Response
{% endif %}
     */
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $documents = $dm->getRepository('{{ bundle }}:{{ document }}')->findAll();

{% if 'annotation' == format %}
        return array('documents' => $documents);
{% else %}
        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:index.html.twig', array('documents' => $documents));
{% endif %}
    }
