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
                    @php
                    $i=1;
                    @endphp

                    @foreach($data['products'] as $key => $val)
                    @php

                    $pWeight = $val->product->weight ? $val->product->weight : '0.2';
                    $weight = $val->quantity * $pWeight;
                    $length = $val->product->length ? $val->product->length : 0;
                    $width = $val->product->width ? $val->product->width : 0;
                    $height = $val->product->height ? $val->product->height : 0;

                    @endphp
                    <RequestedPackages number="{{ $i }}">
                        <Weight>
                            <Value>{{ $weight }}</Value>
                        </Weight>
                        <Dimensions>
                            <Length>{{ $length }}</Length>
                            <Width>{{ $width }}</Width>
                            <Height>{{ $height}}</Height>
                        </Dimensions>
                    </RequestedPackages>
                    @php
                    $i++;
                    @endphp
                    @endforeach
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
