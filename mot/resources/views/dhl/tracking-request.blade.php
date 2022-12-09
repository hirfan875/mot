@php
echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <s:Header>
        <o:Security xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" s:mustUnderstand="1">
            <o:UsernameToken u:Id="uuid-bb6dfb99-e755-4bb5-9338-31b1d09e4a87-111">
                <o:Username>{{ $data['dhl_username'] }}</o:Username>
                <o:Password>{{ $data['dhl_pwd'] }}</o:Password>
            </o:UsernameToken>
        </o:Security>
    </s:Header>
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <trackShipmentRequest>
            <trackingRequest>
                <TrackingRequest>
                    <Request>
                        <ServiceHeader>
                            <MessageTime>{{ $data['messageTime'] }}</MessageTime>
                            <MessageReference>{{ $data['messageReference'] }}</MessageReference>
                        </ServiceHeader>
                    </Request>
                    <AWBNumber>
                        <ArrayOfAWBNumberItem>{{ $data['awbNumber'] }}</ArrayOfAWBNumberItem>
                    </AWBNumber>
                    <LevelOfDetails>{{ $data['levelOfDetails'] }}</LevelOfDetails>
                    <PiecesEnabled>{{ $data['piecesEnabled'] }}</PiecesEnabled>
                </TrackingRequest>
            </trackingRequest>
        </trackShipmentRequest>
    </s:Body>
</s:Envelope>