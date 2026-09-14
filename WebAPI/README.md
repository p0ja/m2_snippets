# Vendor_OrderSynchronizer

REST endpoint through which an ERP reports the synchronization status of orders. Every status is kept in
`vendor_order_erp_status` (shown in the "ERP Status History" tab of the admin order view) and the latest one is set
as `current_erp_status` of the order (a hidden "ERP Status" column of the sales order grid).

Install to `app/code/Vendor/OrderSynchronizer`.

## Access

The endpoint requires the ACL resource *Sales > Operations > Orders > Actions > ERP Synchronization Status*.
Give it to an integration (System > Integrations, then use its access token) or to an admin role:

```
POST /rest/V1/integration/admin/token
Content-Type: application/json

{"username": "apiuser", "password": "*******"}
```

## Request

```
POST /rest/V1/vendor-ordersynchronizer/setOrderStatus
Content-Type: application/json
Authorization: Bearer {access-token}

{"statuses": [
    {
        "increment_id": "000000001",
        "erp_order_id": "ERP-000123",
        "status": "accepted",
        "description": "Imported to the ERP"
    }
]}
```

| Field          | Required | Description                                                               |
|----------------|----------|---------------------------------------------------------------------------|
| `increment_id` | yes      | Magento order increment id, max. 32 characters                            |
| `erp_order_id` | yes      | Order id in the ERP, max. 32 characters                                   |
| `status`       | yes      | `new`, `waiting`, `accepted`, `synchronized`, `rejected` or `error`       |
| `description`  | no       | Additional information                                                    |

The request is saved completely or not at all: nothing is saved when one item is invalid or its order does not
exist.

## Response

| HTTP status | Body                                                                      |
|-------------|---------------------------------------------------------------------------|
| 200         | number of saved statuses, e.g. `1`                                        |
| 400         | invalid items, every error is listed in `errors`                          |
| 401         | missing or invalid token, or the token lacks the ACL resource            |
| 404         | an order with the increment id does not exist                             |
