# -*- coding: utf-8 -*-

from proj.apps.seo.models import config


def context_processor(request):

    _config = config.objects.all()   
    
    if _config:
        config = _config[0]
        return {}      
    else:
        return {}

