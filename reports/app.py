#!/usr/bin/env python

from http.server import BaseHTTPRequestHandler, HTTPServer
import test

# HTTPRequestHandler class
class testHTTPServer_RequestHandler(BaseHTTPRequestHandler):

  # GET
    def do_GET(self):
        filename = test.getme()
        # Send response status code
        self.send_response(200)

        # Send headers
        self.send_header('Content-type','text/html')
        self.end_headers()

        with open('index.html', 'r') as myfile:
            data=myfile.read().replace('\n', '')
        # Send message back to client
        message = '<a href="http://pclotherp.tk/api/reports/'+filename+'">Hello</a>'
        # Write content as utf-8 data
        self.wfile.write(bytes(data, "utf8"))
        return

def run():
  print('starting server...')

  # Server settings
  # Choose port 8080, for port 80, which is normally used for a http server, you need root access
  server_address = ('0.0.0.0', 8081)
  httpd = HTTPServer(server_address, testHTTPServer_RequestHandler)
  print('running server...')
  httpd.serve_forever()


run()
