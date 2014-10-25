
class PageAdmin(object):
	# removed content	
	pass	


class HomeAdmin(PageAdmin, admin.ModelAdmin):
	pass		


def auto_admin_register():
	""" Automaticamente registra no admin classes que são subclasses de PageAdmin e ModelAdmin
	
	Para que isso seja possível é preciso seguir uma convenção:
		O nome de uma classe que herda de PageAdmin e ModelAdmin deve ter a assinatura:
		nomedomodel(de models.py) + 'Admin'. Exemplo:		
		Suponhamos que você tenha em models.py um model chamado 'Person', logo:
		class PersonAdmin(PageAdmin, admin.ModelAdmin):
			pass
	
	Essa função destina-se a evitar escrever tediosamente (e até mesmo esquecer) vários
	"admin.site.register" para cada model admin criado.	
	
	"""
	import sys, inspect
		
	current_module = sys.modules[__name__]
	#bulk register
	#padrão: nome do model + 'Admin' - ie.: modelnameAdmin
	for (name, obj) in inspect.getmembers(current_module, inspect.isclass):	
		if issubclass(obj, PageAdmin) and issubclass(obj, admin.ModelAdmin):
			admin_modelname = obj.__name__
			#obter real nome do model removendo a parte final: 'Admin'
			real_modelname = admin_modelname[0:admin_modelname.index('Admin')]
			modelclass = getattr(current_module, real_modelname)
			admin.site.register(modelclass, obj)