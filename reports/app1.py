import cherrypy
import os, os.path
from jinja2 import Environment, FileSystemLoader
import Costing_details
import Costing_summary

env = Environment(loader=FileSystemLoader('templates'))

class Reports(object):
    def renderTemplate(self, node, title):
        node.heading.content = title
    @cherrypy.expose
    def index(self, module="", name=""):
        if(module == ""):
            return '{"error":"Module Not Found"}'
        else:
            tmpl = env.get_template('index.html')
            return tmpl.render(module=module,name=name)

    @cherrypy.expose
    def Costing_details(self, type="", name=""):
        if(type == "details"):
            filename = Costing_details.getxlsx("Costing_"+type, name)
        else:
            filename = Costing_summary.getxlsx("Costing_"+type, name)
        return '{"file":"' + filename + '"}'

cherrypy.config.update({'server.socket_host':'0.0.0.0','server.socket_port':8081})
if __name__ == '__main__':
    cherrypy.quickstart(Reports())
