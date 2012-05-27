
    /**
     * Finds and displays a {{ document }} document.
     *
{% if 'annotation' == format %}
     * @Route("/{id}/show", name="{{ route_name_prefix }}_show")
     * @Template()
{% endif %}
     */
    public function showAction($id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('{{ bundle }}:{{ document }}')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find {{ document }} document.');
        }
{% if 'delete' in actions %}

        $deleteForm = $this->createDeleteForm($id);
{% endif %}
{% if 'annotation' == format %}
    {% if 'delete' in actions %}

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        );
    {% else %}

        return array('document' => $document);
    {% endif %}
{% else %}
    {% if 'delete' in actions %}

        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:show.html.twig', array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        ));
    {% else %}

        return $this->render('{{ bundle }}:{{ document|replace({'\\': '/'}) }}:show.html.twig', array('document' => $document));
    {% endif %}
{% endif %}
}
