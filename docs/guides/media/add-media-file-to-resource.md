## Adding Media File to a resource

The entity `File` in `EnhavoMediaBundle` is enhavo\'s way of handling
user uploaded files like images or documents. This is a guide on how to
add attached files to your resource.

1)  Adding a single attached file to a resource
2)  Adding a collection of attached files to a resource
3)  Saving additional information about each file
4)  Showing an information field above the file form element

### Adding a single attached file to a resource

To add a single attached file to our resource, first we have to add it
to our Doctrine definition. We should use the oneToOne relation, because
this way we can use the *orphanRemoval* parameter to automatically clean
up attached files if our resource gets deleted.

```yaml
manyToOne:
    file:
        cascade: ['persist', 'refresh']
        targetEntity: Enhavo\Bundle\MediaBundle\Model\FileInterface
```

We also have to add the member variable as well as getter/setter to our
Entity class, of course.

```php
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class MyResource implements ResourceInterface
{

    ...

    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * Set file
     *
     * @param $file FileInterface|null
     */
    public function setFile(FileInterface $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return FileInterface|null
     */
    public function getFile()
    {
        return $this->file;
    }

    ...
```

Now for the user to be able to upload files, we can use the form type
`enhavo_files` in our form. For a single file we need to set the
parameter *multiple* to false.

```php
$builder->add('file', 'enhavo_files', array(
    'label' => 'form.label.file',
    'translation_domain' => 'AcmeMyResourceBundle',
    'multiple' => false
));
```

That\'s it, now the resource has an attached file.

### Adding a collection of attached files to a resource

Maybe we don\'t want a single attached file, but any number of files,
e.g. for pictures in a gallery. To do so, instead of a OneToOne
relationship, we use a ManyToMany relationship in our Doctrine
definition. We also add the *onDelete: cascade* option to the join
tables columns to automatically clean up any attached files if the
resource gets deleted.

::: warning Note
Don\'t use a OneToMany relationship! It will cause doctrine to generate
a foreign key column in the entity_file table, thus losing the loose
coupling between `File` and its parents and potentially causing problems
with other resources using `File`.
:::

```yaml
manyToMany:
    files:
        cascade: ['persist', 'refresh', 'remove']
        targetEntity: Enhavo\Bundle\MediaBundle\Model\FileInterface
        joinTable:
            name: acme_myresource_files
            joinColumns:
                myresource_id:
                    referencedColumnName: id
                    onDelete: cascade
            inverseJoinColumns:
                file_id:
                    referencedColumnName: id
                    onDelete: cascade
```

We also add the member variable as well as getter/setter to our Entity
class.

```php
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class MyResource implements ResourceInterface
{

    ...

    /**
     * @var ArrayCollection
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add file
     *
     * @param FileInterface $file
     */
    public function addFile(FileInterface $file)
    {
        $this->files[] = $file;
    }

    /**
     * Remove file
     *
     * @param FileInterface $file
     */
    public function removeFile(FileInterface $file)
    {
        $this->files->removeElement($file);
    }

    ...
```

Again in the resources form definition, we use the form type
`enhavo_files`. But this time we set the parameter *multiple* to true.
Since this is the default value of the parameter, we can omit it as
well.

```php
$builder->add('files', 'enhavo_files', array(
    'label' => 'form.label.file',
    'translation_domain' => 'AcmeMyResourceBundle',
    'multiple' => true
));
```

Now the file has multiple attached files.

### Saving additional information about each file

The `File` type allows us to save additional information about each
uploaded file. These are saved in the member variable `parameters` as
key-value pairs.

To allow the user to edit this information, we define the fields in our
resources form definition.

```php
$builder->add('file', 'enhavo_files', array(
    'label' => 'form.label.file',
    'translation_domain' => 'AcmeMyResourceBundle',
    'fields' => array(
        'title' => array(
            'label' => 'media.form.label.title',
            'translationDomain' => 'EnhavoMediaBundle'
        ),
        'alt_tag' => array(
            'label' => 'media.form.label.alt_tag',
            'translationDomain' => 'EnhavoMediaBundle'
        ),
        'my_parameter' => array(
            'label' => 'myresource.form.label.my_parameter',
            'translationDomain' => 'AcmeMyResourceBundle'
        )
    )
));
```

The fields *title* and *alt_tag* are the default values that will be
added if the parameter *fields* is omitted.

### Showing an information field above the file form element

We can add additional information for the user to the form element of
type `enhavo_files` by setting the optional parameter `information`.
It\'s a simple array, and the contents will be displayed as a bulletin
list above the thumbnails.

```php
$builder->add('file', 'enhavo_files', array(
    'label' => 'form.label.file',
    'translation_domain' => 'AcmeMyResourceBundle',
    'information' => array(
        'Upload your cute cat pictures here',
        'No dogs allowed'
    )
));
```
