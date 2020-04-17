Comments Action
===============

This action redirects the user to the comments that have been posted on an article, blog entry or similar. The class for which this action should be used must implement the CommentSubjectInterface
like for example the entity :class:`Enhavo\Bundle\ArticleBundle\Entity\Article.php`

.. csv-table::
    :widths: 50 150

    Type , comment
    Options ,"- | :ref:`route <route_comments>`
    - | :ref:`label <label_comments>`
    - | :ref:`translation_domain <translation_domain_comments>`
    - | :ref:`icon <icon_comments>`
    - | :ref:`permission <permission_comments>`
    - | :ref:`hidden <hidden_comments>`"
    Class, :class:`Enhavo\\Bundle\\CommentBundle\\Action\\CommentsActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`

Options
-------

.. _route_comments:
.. |default_route| replace:: `enhavo_comment_comment_index`
.. include:: /reference/action/option/route.rst

.. _label_comments:
.. |default_label| replace:: `comment.label.comments`
.. include:: /reference/action/option/label.rst

.. _translation_domain_comments:
.. |default_translationDomain| replace:: `EnhavoCommentBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_comments:
.. |default_icon| replace:: `comment`
.. include:: /reference/action/option/icon.rst

.. _permission_comments:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_comments:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst


