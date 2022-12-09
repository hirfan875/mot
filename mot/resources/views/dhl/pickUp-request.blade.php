@php
echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"
            xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <s:Header>
        <o:Security xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"
                    s:mustUnderstand="1">
            <o:UsernameToken u:Id="uuid-bb6dfb99-e755-4bb5-9338-31b1d09e4a87-111">
                <o:Username>{!! $data['dhl_username'] !!}</o:Username>
                <o:Password>{!! $data['dhl_pwd'] !!}</o:Password>
            </o:UsernameToken>
        </o:Security>
    </s:Header>
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <PickUpRequest>
            <MessageId>{{ $data['message_id'] }}</MessageId>
            <PickUpShipment>
                <ShipmentInfo>
                    <ServiceType>U</ServiceType>
                    <Billing>
                        <ShipperAccountNumber>{{ $data['dhl_account_number'] }}</ShipperAccountNumber>
                        <ShippingPaymentType>S</ShippingPaymentType>
                    </Billing>
                    <UnitOfMeasurement>SI</UnitOfMeasurement>
                </ShipmentInfo>
                <PickupTimestamp>{{ $data['pickup_time'] }}</PickupTimestamp>
                <PickupLocationCloseTime>{{ $data['location_close_time'] }}</PickupLocationCloseTime>
                <SpecialPickupInstruction>{{ $data['special_instruction'] }}</SpecialPickupInstruction>
                <PickupLocation>{{ $data['pickup_location'] }}</PickupLocation>

                <Ship>
                    <Shipper>
                        <Contact>
                            <PersonName>{{ $data['sPersonName'] }}</PersonName>
                            <CompanyName>{{ $data['sCompanyName'] }}</CompanyName>
                            <PhoneNumber>{{ $data['sPhoneNumber'] }}</PhoneNumber>
                            <EmailAddress>{{ $data['sEmailAddress'] }}</EmailAddress>
                            <MobilePhoneNumber>{{ $data['sPhoneNumber'] }}</MobilePhoneNumber>
                        </Contact>
                        <Address>
                            <StreetLines>{{ $data['sStreetLines'] }}</StreetLines>
                            <City>{{ $data['sCity'] }}</City>
                            <PostalCode>{{ $data['sPostalCode'] }}</PostalCode>
                            <CountryCode>{{ $data['sCountryCode'] }}</CountryCode>
                        </Address>
                    </Shipper>
                    <Recipient>
                        <Contact>
                            <PersonName>{{ $data['rPersonName'] }}</PersonName>
                            <CompanyName>{{ $data['rCompanyName'] }}</CompanyName>
                            <PhoneNumber>{{ $data['rPhoneNumber'] }}</PhoneNumber>
                            <EmailAddress>{{ $data['rEmailAddress'] }}</EmailAddress>
                            <MobilePhoneNumber>{{ $data['rPhoneNumber'] }}</MobilePhoneNumber>
                        </Contact>
                        <Address>
                            <StreetLines>{{ $data['rStreetLines'] }}</StreetLines>
                            <City>{{ $data['rCity'] }}</City>
                            <PostalCode>{{ $data['rPostalCode'] }}</PostalCode>
                            <CountryCode>{{ $data['rCountryCode'] }}</CountryCode>
                        </Address>
                    </Recipient>
                </Ship>
                <Packages>
                    <RequestedPackages number="1">
                        <Weight>{{ $data['weight'] }}</Weight>
                        <Dimensions>
                            <Length>{{ $data['length'] }}</Length>
                            <Width>{{ $data['width'] }}</Width>
                            <Height>{{ $data['height'] }}</Height>
                        </Dimensions>
                    </RequestedPackages>
                </Packages>
            </PickUpShipment>
        </PickUpRequest>
    </s:Body>
</s:Envelope>