# Shopify API PHP Client

A PHP client for interacting with the Shopify API, designed to simplify the integration of Shopify functionalities into your PHP applications.

## Features

- **Order Management:** Create, update, cancel, close, and delete orders, as well as retrieve individual or multiple orders.
- **Product Management:** Find and list products, providing a streamlined interface for interacting with product details on Shopify.
- **Webhook Management:** Create and manage Shopify webhooks, allowing for real-time notifications on specified events.
- **Inventory Management:** Retrieve inventory items, aiding in stock level synchronization and management.
- **Theme Management:** Access and manage Shopify themes, facilitating theme customization and configuration.
- **Asset Management:** Handle theme assets, enabling the uploading, updating, and deleting of theme files and assets.
- **Fulfillment Management:** Manage order fulfillments, including the retrieval and cancellation of fulfillment orders.
- **Transaction Management:** Facilitate the creation of transactions, essential for order payment processing.
- **Shop Management:** Retrieve shop details, providing essential information about the Shopify store configuration.

## Usage

Instantiate the client with your Shopify store URL and access token:

```php
$client = new Jeanvcastro\ShopifyApiPhp\Client('your-shopify-store.myshopify.com', 'your-access-token');
```

Utilize the service classes to interact with different aspects of the Shopify API. For example, to manage orders:

```php
$orderService = new Jeanvcastro\ShopifyApiPhp\OrderService($client);
```

Ensure you have Composer installed and set up in your project to manage dependencies.

## Contributing

Contributions to the Shopify API PHP client are welcome. Please feel free to fork the repository, make improvements, and submit pull requests.

## License

This project is licensed under the [MIT License](LICENSE).

---

Developed with ❤️ by [@jeanvcastro](https://github.com/jeanvcastro)
