Comments Action
===============

This action redirects the user to the comments that exist on an article, blog entry or similar. The class for which this action should be used must implement the CommentSubjectInterface
like for example the entity :class: Enhavo\Bundle\ArticleBundle\Entity\Article.php

.. csv-table::
    :widths: 50 150

    Type , comment
    Options ,"- | :ref:`route <route>`"
    Inherited Options, "- | :ref:`label <label>`
    - | :ref:`translation_domain <translation_domain>`
    - | :ref:`icon <icon>`
    - | :ref:`permission <permission>`
    - | :ref:`hidden <hidden>`"
    Class, :class:`Enhavo\\Bundle\\CommentBundle\\Action\\CommentsActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType <abstract-action>`

Options
-------

.. _route:
.. |default_route| replace:: `enhavo_comment_comment_index`
.. include:: /reference/action/option/route.rst

Inherited Options
-----------------

.. _label:
.. |default_label| replace:: `comment.label.comments`
.. include:: /reference/action/option/label.rst

.. _translation_domain:
.. |default_translationDomain| replace:: `EnhavoCommentBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon:
.. |default_icon| replace:: `comment`
.. include:: /reference/action/option/icon.rst

.. _permission:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst


