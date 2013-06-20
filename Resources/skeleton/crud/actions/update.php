
    /**
     * Edits an existing {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/{id}/update", name="{{ route_name_prefix }}_update")
     * @Method("POST")
     * @Template("{{ bundle }}:{{ controller_namespace ? controller_namespace|replace({"\\": "/"}) ~ '/' : '' }}{{ document }}:edit.html.twig")
     *
     * @param Request $request The request object
     * @param string $id       The document ID
     *
     * @return array
{% else %}
     * @param Request $request The request object
     * @param string $id       The document ID
     *
     * @return \Symfony\Component\HttpFoundation\Response
{% endif %}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('{{ bundle }}:{{ document }}')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find {{ document }} document.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createForm(new {{ document_class }}Type(), $document);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('{{ route_name_prefix }}_edit', array('id' => $id)));
        }

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
