import xlsxwriter
# For images
import os
from io import BytesIO
import sys
from PIL import Image
import requests
from pymongo import MongoClient
from bson.objectid import ObjectId

client = MongoClient('mongodb://administrator:123123q@localhost:27017/')
db = client.test
collection = db.Costing.details

def getxlsx(module="", name=""):
    if(module == ""):
        try:
            filename = sys.argv[1] + '.xlsx'
        except IndexError:
            filename = 'demo.xlsx'
    else:
        filename = module + '.xlsx'

    docs = collection.find({"$text":{"$search":name}})

    # Import urlopen() for either Python 2 or 3.
    try:
        from urllib.request import urlopen
    except ImportError:
        from urllib2 import urlopen

    # Create an new Excel file and add a worksheet.
    workbook = xlsxwriter.Workbook(filename)
    for doc in docs:
        worksheet = workbook.add_worksheet(doc['name'])

        # Widen the first column to make the text clearer.
        worksheet.set_column('A:A', 20)
        worksheet.set_column('D:D', 15)
        worksheet.set_column('F:F', 15)

        # Add a bold format to use to highlight cells.
        bold = workbook.add_format({'bold': True})

        # Write some simple text.
        worksheet.write('A1', 'Buyer')
        if (doc['buyer'] == 'domestic'):  worksheet.write('B1', doc['domestic'])
        if (doc['buyer'] == 'export'):  worksheet.write('B1', doc['export'])
        worksheet.write('A2', 'Division')
        if (doc['buyer'] == 'domestic'):  worksheet.write('B2', doc['ddivision'])
        if (doc['buyer'] == 'export'):  worksheet.write('B2', doc['xdivision'])
        worksheet.write('A3', 'Brand')
        if (doc['buyer'] == 'domestic'):  worksheet.write('B3', doc['dbrand'])
        if (doc['buyer'] == 'export'):  worksheet.write('B3', doc['xbrand'])
        worksheet.write('A4', 'Season')
        worksheet.write('B4', doc['season'])

        # Text with formatting.
        border = workbook.add_format({'border':1,'bold':True})
        bo = workbook.add_format({'border':1})

        # worksheet.set_row(8, 20, border)
        worksheet.write('A5', 'Style #', border)
        worksheet.write('B5', 'Color', border)
        worksheet.write('C5', 'Order Qty', border)
        worksheet.write('D5', 'Garment Desc', border)
        worksheet.write('E5', 'Picture', border)
        worksheet.write('F5', 'Delivery Date', border)
        worksheet.write('G5', 'Delivery', border)
        worksheet.write('H5', 'Currency', border)
        worksheet.write('I5', 'Price', border)
        worksheet.write('J5', 'Closed Price', border)
        worksheet.write('K5', 'Remark', border)

        worksheet.write('A6',doc['style'], bo)
        worksheet.write('B6',doc['color'], bo)
        worksheet.write('C6',doc['qty'], bo)
        worksheet.write('D6', '', bo)
        worksheet.write('E6', '', bo)
        worksheet.write('F6',doc['deldate'], bo)
        worksheet.write('G6',doc['delivery'], bo)
        worksheet.write('H6',doc['currency'], bo)
        worksheet.write('I6',doc['cost']['quoted'], bo)
        worksheet.write('J6',doc['cost']['closed'], bo)
        worksheet.write('K6',doc['remark'], bo)

        # worksheet.write('A8', str(doc))

        # Write some numbers, with row/column notation.
        # worksheet.write(2, 0, 123)
        # worksheet.write(3, 0, 123.456)

        # Insert an image from url.
        if 'pic' in doc:
            if doc['pic']: url = 'http://138.197.220.205:3002/upload/'+doc['pic']
            else: url = 'http://www.tompetty.com/sites/g/files/g2000004366/f/Sample-image10-highres.jpg'
        else: url = 'http://www.tompetty.com/sites/g/files/g2000004366/f/Sample-image10-highres.jpg'
        basewidth = 50
        img = Image.open(BytesIO(requests.get(url).content))
        wpercent = (basewidth/float(img.size[0]))
        hsize = int((float(img.size[1])*float(wpercent)))
        img = img.resize((basewidth,hsize), Image.ANTIALIAS)
        # image_data = BytesIO(urlopen(url).read())
        output = BytesIO()
        img.save(output,format="jpeg")
        output.seek(0)
        image_data = output
        worksheet.insert_image('E6', url, {'image_data': image_data})
        # worksheet.insert_image('B5','cat.jpg')

    workbook.close()

    return filename
