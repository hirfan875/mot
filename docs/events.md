Event Driven Processing
------------------------

Mall Of Turkey offloads as much processing to external processes as it can.
As previously mentioned, any email being sent to a user should be sent though external processes. Currently, Laravel raises Notification. All such notifications should be processed an external process.

Following events needs to be raised so that we can hook these and perform additional tasks when needed.

Product Events
--------------

1. Product Added.
2. Product Edited.
3. Product Price Changed.
4. Product Removed from Market.

Promotion Events
----------------

Promotion Activated.        []
Promotion Deactivated.
Promotion Came into Effect [Perhaps due to the start time]
Promotion Goes out of Effect [Perhaps due to the end time]

Go through cart products and update prices. Add Message for the customer to inform the change.


Store Enabled
Store Disabled

Order Events
------------

Store Order Created
Store Order Status Changed
