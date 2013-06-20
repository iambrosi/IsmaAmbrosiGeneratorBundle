
    /**
     * Creates a new {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/create", name="{{ route_name_prefix }}_create")
     * @Method("POST")
     * @Template("{{ bundle }}:{{ controller_namespace ? controller_namespace|replace({"\\": "/"}) ~ '/' : '' }}{{ document }}:new.html.twig")
     *
     * @param Request $request
     *
     * @return array
{% else %}
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
{% endif %}
     */
    public function createAction(Request $request)
    {
        $document = new {{ document_class }}();
        $form     = $this->createForm(new {{ document_class }}Type(), $document);
        $form->bind($request);

        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

{% if 'show' in actions %}
            return $this->redirect($this->generateUrl('{{ route_name_prefix }}_show', array('id' => $document->getId())));
{% else %}
            return $this->redirect($this->generateUrl('{{ route_name_prefix }}'));
{% endif %}
        }

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
