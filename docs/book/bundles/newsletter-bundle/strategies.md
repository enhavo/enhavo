## Strategies

Enhavo has by default three different strategies to add subscriber to a
storage. Here you will find how the strategy works and how to configure
them.

### Notify Strategy

The notify strategy just inform the admin and/or the subscriber that the
subscriber was added to the storage.

```yaml
enhavo_newsletter:
    strategy:
        settings:
            notify:
                <OPTIONS>
```

  ------------------------------ -------------------------------------------------
  **Option**                     Description

  **notify**                     A boolean if the subscriber should be notified

  **check_exists**               Check if email already exists in validation

  **subject**                    Subscriber subject

  **translation_domain**         Subscriber Translation domain

  **template**                   The subscriber template

  **from**                       From email

  **admin_notify**               A boolean if the admin should be notified

  **admin_template**             Admin email template

  **admin_email**                Send to address

  **admin_subject**              Admin email subject

  **admin_translation_domain**   Admin translation domain
  ------------------------------ -------------------------------------------------

### Accept Strategy

With accept strategy a user can sign up for a newsletter but need to be
accepted by an admin before he will be added to the storage. After a
user subscribe, the admin will receive an email with the subscriber
information. The admin can add the user by clicking on a link in the
email.

```yaml
enhavo_newsletter:
    strategy
        settings:
            accept:
                <OPTIONS>
```

  ------------------------------ -------------------------------------------------
  **Option**                     Description

  **notify**                     A boolean if the subscriber should be notified

  **subject**                    Subscriber subject

  **translation_domain**         Subscriber Translation domain

  **template**                   The subscriber template

  **from**                       From email

  **admin_template**             Admin email template

  **admin_email**                Send to address

  **admin_subject**              Admin email subject

  **admin_translation_domain**   Admin translation domain

  **activation_template**        Template for the activation site
  ------------------------------ -------------------------------------------------

### Double Opt-In Strategy

With the double opt-in strategy, the subscriber need to confirm his
entry before he will be added to the storage.

```yaml
enhavo_newsletter:
    strategy
        settings:
            double_opt_in:
                <OPTIONS>
```

  ------------------------------ -------------------------------------------------
  **Option**                     Description

  **notify**                     A boolean if the subscriber should be notified

  **subject**                    Subscriber subject

  **translation_domain**         Subscriber Translation domain

  **template**                   The subscriber template

  **from**                       From email

  **admin_notify**               A boolean if the admin should be notified

  **admin_template**             Admin email template

  **admin_email**                Send to address

  **admin_subject**              Admin email subject

  **admin_translation_domain**   Admin translation domain

  **activation_template**        Template for the activation site
  ------------------------------ -------------------------------------------------
