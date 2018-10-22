import sys
import os
import shlex
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer

lexers['php'] = PhpLexer(startinline=True, linenos=1)
lexers['php-annotations'] = PhpLexer(startinline=True, linenos=1)

extensions = [
    'sphinx.ext.autodoc',
]
source_suffix = '.rst'
source_encoding = 'utf-8'
master_doc = 'index'
project = u'enhavo'
copyright = u'2018, xq web'
author = u'xq web'
version = '0.6'
release = '0.6'
language = None
exclude_patterns = []
pygments_style = 'sphinx'
todo_include_todos = False
html_theme = 'enhavo'
html_theme_path = ['../theme']
latex_elements = {}

latex_documents = [
  (master_doc, 'enhavo.tex', u'enhavo Documentation',
   u'xq web', 'manual'),
]

man_pages = [
    (master_doc, 'enhavo', u'enhavo Documentation',
     [author], 1)
]

texinfo_documents = [
  (master_doc, 'enhavo', u'enhavo Documentation',
   author, 'enhavo', 'One line description of project.',
   'Miscellaneous'),
]