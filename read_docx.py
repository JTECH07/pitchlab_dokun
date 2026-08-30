import zipfile
import xml.etree.ElementTree as ET
import sys

def read_docx(path):
    with zipfile.ZipFile(path) as docx:
        tree = ET.fromstring(docx.read('word/document.xml'))
    namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
    text = []
    for paragraph in tree.iterfind('.//w:p', namespaces):
        para_text = "".join([node.text for node in paragraph.iterfind('.//w:t', namespaces) if node.text])
        if para_text:
            text.append(para_text)
    return '\n'.join(text)

if __name__ == '__main__':
    with open('parsed_docx.txt', 'w', encoding='utf-8') as f:
        f.write(read_docx('DOKUN_Cahier_des_charges_PitchLab2026.docx'))
