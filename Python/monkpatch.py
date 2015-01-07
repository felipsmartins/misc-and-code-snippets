#coding: utf-8
import sys, inspect

def make_patch():
	current_module = sys.modules[__name__]

	for (name, obj) in inspect.getmembers(current_module, inspect.isclass):
		#sobrescreve o método save somente nas subclasses da classe Page
		if issubclass(obj, Page) and obj.__name__ != 'Page':
			setattr(obj, 'save', onsave_page)
			
