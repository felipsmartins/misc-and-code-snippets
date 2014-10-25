class ExempleModelManager(models.Manager):	
	def select(self, page_id, language):
		queryset = self.filter(pagina_id=page_id)
		return utils.select_from_qs(queryset, language)