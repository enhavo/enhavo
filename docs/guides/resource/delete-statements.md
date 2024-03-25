## Delete Statements

There are three different statements in doctrine to delete. This entry
shows your possibilities and help you to decide which statement should
use.

To understand the statements we will apply them on an example. Think of
books and chapters, where book is the parent and chapter is the child
and owning entity.

![image](/images/book-chapter-er.png)

### Orphan Removal

Orphan removal delete a child entity on flush if its relation changed.
So you need to apply orphan removal on book. You will use Orphan removal
every time you use child elements that can be added and deleted. For
example in a CollectionType in Symfony forms. Orphan removal don\'t have
any effects if you just delete books. So if a book will be deleted, the
chapter entities still exists.

```yaml
Book:
    oneToMany:
        chapters:
            targetEntity: Chapter
            mappedBy: book
            orphanRemoval: true

Chapter:
    manyToOne:
        book:
            targetEntity: Book
```

So change mean, if you call a setter and change the current member
variable of chapter.

```php
<?php

$chapter->setBook($book);     // Set it first time
$em->flush();

$chapter->setBook($book);     // nothing changed
$chapter->setBook($otherBook) // member variable changed
$chapter->setOtherBook($book) // member variable changed
$em->flush();                 // Orphan removal will be triggered
```

So if you work with the book class, you need to add setter to it,
because chapter is the child but the owning entity

```php
<?php

class Book {

    public function addChapter(Chapter $chapter)
    {
        $chapter->setBook($this); // This line need to be added
        $this->chapters[] = $chapter;
        return $this;
    }

    public function removeChapter(Chapter $chapter)
    {
        $chapter->setBook(null); // This line need to be added
        $this->chapters->removeElement($chapter);
    }
```

### Cascade Remove

Cascade remove will call a doctrine remove on all child elements if it
was called on the parent. You apply a cascade remove on the parent
entity. Normally you use it, if the child element only exists with a
parent. So a chapter never exists alone, it will need a book.

```yaml
Book:
    oneToMany:
        chapters:
            cascade: ['remove']
            targetEntity: Chapter
            mappedBy: book

Chapter:
    manyToOne:
        book:
            targetEntity: Book
```

```php
<?php

$book->addChapter($chapter)
$em->remove($book);
$em->flush();   // chapter will also be deleted, $em->remove($chapter) will be called automatically
```

### On Delete Cascade

On delete cascade is a mysql feature and will remove all child elements
if the parent was deleted. So it\'s like the cascade remove from
doctrine, but you need to apply it on the child entity instead of the
parent. Because it\'s a mysql feature, doctrine won\'t take notice if
the child will be deleted. Normally that\'s that a problem, as long as
you don\'t work with the child after you delete the parent. Like cascade
remove, you use it if the child element only exists with a parent.

```yaml
Book:
    oneToMany:
        chapters:
            targetEntity: Chapter
            mappedBy: book

Chapter:
    manyToOne:
        book:
            targetEntity: Book
            joinColumn:
                onDelete: CASCADE
```

```php
<?php

$book->addChapter($chapter)
$em->remove($book);
$em->flush();   // chapter will also be deleted by mysql. You should avoid to go on working with $chapter
```
