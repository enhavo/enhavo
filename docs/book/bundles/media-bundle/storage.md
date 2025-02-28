## Storage

As we already learn, the file entity represents a single file and therefor it can be saved to the database,
but where are the content of the file will be stored? First of all, if you create a file entity, weather by
the factory or form, it will never touch or move the original file. If the file entity will be saved to the 
database, the storage will copy the file to an internal storage and then the file entity reference to that stored file.
If you retrieve a file entity from the database, it will reference to the stored file automatically.
This is all done by doctrine hooks, so this happens also if you work with doctrine directly.

