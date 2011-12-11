
    /**
     * Displays a form to edit an existing {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/{id}/edit", name="{{ route_name_prefix }}_edit")
     * @Template()
{% endif %}
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $document = $dm->getRepository('{{ bundle }}:{{ document }}')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find {{ document }} document.');
        }

        $editForm = $this->createForm(new {{ document_class }}Type(), $document);
        $deleteForm = $this->createDeleteForm($id);

{% if 'annotation' == format %}
        return array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
{% else %}
        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:edit.html.twig', array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
{% endif %}
    }
