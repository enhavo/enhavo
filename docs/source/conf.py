import sys
import os
import shlex

# Add extension to module lookup path
sys.path.append(os.path.abspath('../extension'))

from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer

lexers['php'] = PhpLexer(startinline=True, linenos=1)
lexers['php-annotations'] = PhpLexer(startinline=True, linenos=1)

extensions = [
    'sphinx.ext.autodoc', 'enhavo.sphinx'
]
source_suffix = '.rst'
source_encoding = 'utf-8'
master_doc = 'index'
project = u'enhavo'
copyright = u'2021, xq web'
author = u'xq web'
version = '0.10'
release = '0.10'
language = 'en'
exclude_patterns = []
todo_include_todos = False
pygments_style = 'sphinx'
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
