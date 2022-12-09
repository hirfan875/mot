@php
echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <s:Header>
        <o:Security xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" s:mustUnderstand="1">
            <o:UsernameToken u:Id="uuid-bb6dfb99-e755-4bb5-9338-31b1d09e4a87-111">
                <o:Username>{!! $data['dhl_username'] !!}</o:Username>
                <o:Password>{!! $data['dhl_pwd'] !!}</o:Password>
            </o:UsernameToken>
        </o:Security>
    </s:Header>
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <RateRequest>
            <ClientDetail>
            </ClientDetail>
            <RequestedShipment>
                <DropOffType>{!! $data['DropOffType'] !!}</DropOffType>
                <Ship>
                    <Shipper>
                        <StreetLines>{!! $data['sStreetLines'] !!}</StreetLines>
                        <City>{!! $data['sCity'] !!}</City>
                        <PostalCode>{!! $data['sPostalCode'] !!}</PostalCode>
                        <CountryCode>{!! $data['sCountryCode'] !!}</CountryCode>
                    </Shipper>
                    <Recipient>
                        <StreetLines>{!! $data['rAddress'] !!}</StreetLines>
                        <City>{!! $data['rCity'] !!}</City>
                        <PostalCode>{!! $data['rPostalCode'] !!}</PostalCode>
                        <CountryCode>{!! $data['rCountryCode'] !!}</CountryCode>
                    </Recipient>
                </Ship>
                <Packages>
                    
                    <RequestedPackages number="1">
                        <Weight>
                            <Value>0.2</Value>
                        </Weight>
                        <Dimensions>
                            <Length>0</Length>
                            <Width>0</Width>
                            <Height>0</Height>
                        </Dimensions>
                    </RequestedPackages>
                   
                </Packages>
                <ShipTimestamp>{!! $data['pickup_time'] !!}</ShipTimestamp>
                <UnitOfMeasurement>{!! $data['UnitOfMeasurement'] !!}</UnitOfMeasurement>
                <Content>{!! $data['Content'] !!}</Content>
                <DeclaredValue>{!! $data['DeclaredValue'] !!}</DeclaredValue>
                <DeclaredValueCurrecyCode>{!! $data['DeclaredValueCurrecyCode'] !!}</DeclaredValueCurrecyCode>
                <PaymentInfo>{!! $data['PaymentInfo'] !!}</PaymentInfo>
                <Account>{!! $data['dhl_account_number'] !!}</Account>
            </RequestedShipment>
        </RateRequest>
    </s:Body>
</s:Envelope>
