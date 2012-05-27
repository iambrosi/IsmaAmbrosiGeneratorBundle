
    /**
     * Deletes a {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/{id}/delete", name="{{ route_name_prefix }}_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
{% else %}
     * @param string $id The document ID
     *
     * @return \Symfony\Component\HttpFoundation\Response
{% endif %}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $document = $dm->getRepository('{{ bundle }}:{{ document }}')->find($id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find {{ document }} document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('{{ route_name_prefix }}'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
