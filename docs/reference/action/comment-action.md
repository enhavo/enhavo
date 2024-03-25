## Comment

This action redirects the user to the comments that have been posted on
an article, blog entry or similar. The class for which this action
should be used must implement the `CommentSubjectInterface` like for
example the entity `Enhavo\Bundle\ArticleBundle\Entity\Article`


<ReferenceTable
    type="comment"
    className="Enhavo\Bundle\CommentBundle\Action\CommentsActionType"
    parent="Enhavo\Bundle\AppBundle\ActionAbstractActionType"
>
  <template v-slot:inherit>
    <ReferenceOption name="label" type="comment" />,
    <ReferenceOption name="route" type="comment" />,
    <ReferenceOption name="translation_domain" type="comment" />,
    <ReferenceOption name="icon" type="comment" />,
    <ReferenceOption name="permission" type="comment" />,
    <ReferenceOption name="hidden" type="comment" />
  </template>
</ReferenceTable>


