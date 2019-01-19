 DoctrineFootprintExtension
 ==========================
This extension provides timestamps and user actions tracking for entity create, update and delete.

By using only one trait and listener you can automate update of created_at, created_by, updated_at, updated_by, deleted_at, deleted_by.

Listener will automatically set date times and also usernames into each entity which contains mentioned fields.

Installation
------------
```composer require adrianglazer/DoctrineFootprintExtension```

Setup
------
By default all functionalities are enabled together with soft deletable. If you would like to disable soft deletable you will have to create your own Trait without deletedAt and deletedBy fields.

You have to set a doctrine filter config inside **config/packages/doctrine.yaml**:

```$xslt
doctrine:
    dbal:
        ...
    orm:
        ...
        mappings:
            ...
        filters:
            footprint:
                class: Glazer\DoctrineFootprintExtension\Filter\FootprintFilter
                enabled: true
```

and set a listener inside **config/services.yaml**:

```$xslt
    Glazer\DoctrineFootprintExtension\Listener\FootprintListener:
        class: Glazer\DoctrineFootprintExtension\Listener\FootprintListener
        autowire: true
        tags:
            - { name: doctrine.event_subscriber }
        arguments: ['@security.token_storage']
```

Conclusion
------------
If you set everything correctly your entities will start to update automatically on each user action. It's an out of the box solution designed for simple projects.