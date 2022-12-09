Cart States management

Cart Management for both mobile and web app will be handled by a Cart Management Service. 
In general a cart will be represented by a model call cart.
 
The Cart should have information about at least the following :

* Items included in the Cart.
* Coupon Codes Applied to The Cart
* Users (whether logged in or guest)
* Cart will have following Status 

    * Open      : Cart with status Open will have the ability to add more items.
    * Confirm   : Once a user confirms to buy and proceeds to pay, Cart status will change to Confirm. At that point user cannot add more items to the cart. This is to prevent the same user logged in from other devices and adding more items while a payment process is under-way.
    * Complete  : Payment is complete. 


When a cart status changed to Confirm, we need to take the items out of stock so that no one else can purchase those while payment process is underway.
A cart status may go from Confirm back to Open,  In that case we must return items that we took out of stock back to stock.

The prices of items added to the cart should reflect current prices. Those should not be the prices when the user added item to the cart. That means if price of an item drops due to a promotion, after you added the item to cart, the cart should reflect new price.

We may opt to send a notification at some time to the user about this price change.

Also, the coupon code applied to the cart should be active at the time of checkout, or we should let the user know that the coupon they applied in past, has expired etc.

Items in cart may be marked on hold. That will mean these items will not be part of order. But will stay in the cart. When customer proceeds with order completion, such items will be moved to a new cart.