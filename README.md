
# Indstillinger:

- Enable/disable
- Api Key + Secret ( evt. knap )
- sessiontime
- integrationId
- Mapping
	- Fetch fields from Heyloyalty
	- Fetch fields from magento
drag inspiration af: https://gitlab.wexo.io/wexo/maulund/-/blob/c561597a367c1333363943a1fcbd5c8ce12c9862/app/code/Maulund/Skuvault/Model/Config/Source/ChannelAccounts.php
`toOptionArray` kan stå for at hente information fra externe ressourcer

```xml
<!-- https://gitlab.wexo.io/magento2-modules/approved/shipping/webshipper/-/blob/master/etc/adminhtml/system.xml -->
<field id="sender_address"
       translate="label"
       sortOrder="20"
       showInDefault="1"
       showInWebsite="1"
       showInStore="1">
    <label>Sender Address Mapping</label>
    <backend_model>Wexo\Webshipper\Block\Adminhtml\System\Config\SenderAddress\BackendModel</backend_model>
    <frontend_model>Wexo\Webshipper\Block\Adminhtml\System\Config\SenderAddress\FrontendModel</frontend_model>
    <comment><![CDATA[<a href='https://docs.webshipper.io/#shipping_addresses'>Webshipper Address Documentation</a>]]></comment>
</field>
```
	- Activate Tracking Script
		- flag for cronjob/consumer
		- activates script frontend based on settings

# Forbindelse:

Opret Api Model til al kommunikation til HeyLoyalty

Gerne med interface til preferences overskrivelser ;) 

`Wexo/HeyLoyalty/Api/HeyLoyaltyApiInterface` => `Wexo/Heyloyalty/Model/HeyLoyaltyApi`

Hver metode i api'er skal gerne mappe op mod deres dokumentation med samme parametre, så kan vi lave wrapper metoder til lettere håndtering i magento

Api: https://github.com/Heyloyalty/api/wiki/Getting-started

# Tracking Script:

## Default Fields:

- Firstname
- lastname
- Email
- Mobile no.
- Sex
- Birthday
- Address
- Postalcode
- City
- Country

## Custom Fields:
- Customer ID (If necessary) 
- Customer type (If necessary)
- <fields from mapping>

Implementere vist på alle sider undtagen purchase confirmation
implementere addToBasket enten når man ligger noget i kurven eller når man går ind på kurven
Implementere removeFromBasket når man fjerner et produkt fra kurven
Implementere purchasedBasket når man kommer til purchase confirmation siden


# Purchase History:

Opret en Model klasse der indeholder metoder der kan bruges af cronjob / konsol kommandoer, gerne med input så vi kan debugge specifikke kunder/ordre

Det ønskes af HeyLoyalty at kunne importere en kundens ordre op til 2 år tilbage i tiden.
Magento holder selv styr på ordre per kunde, selvom de opretter en gæstebruger

Mit forslag:

Funktion til liste af ordre der skal importeres ( stoooor liste af id'er )
pseudo query: `select order.order_id from customers as customer left join orders as order on order.customer_id = customer.customer_id where order.is_exported_to_heyloyalty_flag != 1`

# Transactional Emails:

Overskriv Default Magento emails med mulighed for at slå HeyLoyalte mails i stedet.
Dette kan gøres via api kald til Heyloyalty ( webhooks )




# Product Feed?


