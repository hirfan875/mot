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
        <ShipmentRequest xmlns="http://scxgxtt.phx-dc.dhl.com/euExpressRateBook/ShipmentMsgRequest">
            <Request>
                <ServiceHeader>
                    <MessageTime>{{ $data['messageTime'] }}</MessageTime>
                    <MessageReference>{{ $data['messageReference'] }}</MessageReference>
                </ServiceHeader>
            </Request>
            <RequestedShipment>
                <ShipTimestamp>{{ $data['ShipTimestamp'] }}</ShipTimestamp>
                <PaymentInfo>{{ $data['paymentInfo'] }}</PaymentInfo><!--Gumruk Vergisi alıcı tarafından ödenecekse: DAP, gönderici tarafından ödenecekse DDP olmalıdır)-->
                <ShipmentInfo>
                    <DropOffType>REGULAR_PICKUP</DropOffType>
                    <ServiceType>{{ $data['ServiceType'] }}</ServiceType>
                    <Account>{{ $data['dhl_account_number'] }}</Account><!--originally was 310 .. we assumed they wanted complete account number-->
                    <Currency>{{ $data['currency'] }}</Currency>
                    <UnitOfMeasurement>SI</UnitOfMeasurement> <!-- KG ve CM için SI olarak kalmalıdır-->
                    <PackagesCount>1</PackagesCount>
                    <LabelType>PDF</LabelType>
                    <LabelTemplate>ECOM26_84_A4_001</LabelTemplate>
                    <ArchiveLabelTemplate>ARCH_8X4_A4_002</ArchiveLabelTemplate>
                    <CustomsInvoiceTemplate>COMMERCIAL_INVOICE_P_10</CustomsInvoiceTemplate>
                    <PaperlessTradeEnabled>0</PaperlessTradeEnabled> <!--PLT li gönderi yapılması için kullanılır-->
                    <Billing>
                        <ShipperAccountNumber>{{ $data['dhl_account_number'] }}</ShipperAccountNumber>  <!--310 ile başlayan account numaranız-->
                        <ShippingPaymentType>S</ShippingPaymentType> <!-- Karşı ödemeli gönderilerde bu alan R(Receiver) olarak değiştirilir ve BillingAccountNumber tagine alıcı(Receiver) DHL hesap numarası girilir(9 ile başlar)-->
                        <BillingAccountNumber>{{ $data['dhl_account_number'] }}</BillingAccountNumber> <!--310 ile başlayan account numaranız-->
                        <!--<DutyAndTaxPayerAccountNumber></DutyAndTaxPayerAccountNumber>-->
                    </Billing>
                    <!--<SpecialServices>
                            <Service>
                                    <ServiceType>WY</ServiceType> PLT\'li gonderiler için sabittir, PLT\'li olmayan gönderiler için SpecialService tag\'i ve SpecialServiceType elementi kaldırılmalıdır
                            </Service>
                    </SpecialServices>-->
                    <LabelOptions>
                        <DetachOptions>
                            <AllInOnePDF>Y</AllInOnePDF> <!-- Tüm dökümanlar(Waybill, AWB, Receipt) tek dökümanda iletilir -->
                        </DetachOptions>
                        <RequestWaybillDocument>Y</RequestWaybillDocument>
                        <RequestDHLCustomsInvoice>Y</RequestDHLCustomsInvoice>
                        <DHLCustomsInvoiceType>COMMERCIAL_INVOICE</DHLCustomsInvoiceType><!--Proforma invoice için tag de PROFORMA_INVOICE kullanılmalıdır-->
                        <HideAccountInWaybillDocument>N</HideAccountInWaybillDocument>
                    </LabelOptions>
                </ShipmentInfo>
                <SpecialPickupInstruction>fragile items</SpecialPickupInstruction>
                <InternationalDetail> <!-- Uluslarası gönderilerde ürünlerin tanımı, commodity code, customs value gibi değerleri girilebilir-->
                    <Commodities>
                        <NumberOfPieces>{{ $data['numberOfPieces'] }}</NumberOfPieces>
                        <Description>{{ $data['description'] }}</Description> <!--Faturadaki tüm ürunler için açıklama alanı -->
                        <CountryOfManufacture>{{ $data['manufacturingCountryCode'] }}</CountryOfManufacture>
                        <Quantity>{{ $data['quantity'] }}</Quantity> <!-- Ürün adet sayısı-->
                        <UnitPrice>{{ $data['unitPrice'] }}</UnitPrice> <!-- Ürünlerin fiyat bilgisi -->
                        <CustomsValue>{{ $data['customsValue'] }}</CustomsValue> <!-- Ürünlerin gümrük değeri -->
                    </Commodities>
                    <Content>{{ $data['Content'] }}</Content>
                    <ExportDeclaration>
                        <InvoiceDate>{{ $data['invoiceDate'] }}</InvoiceDate> <!--Fatura Tarihi-->
                        <InvoiceNumber>{{ $data['invoiceNumber'] }}</InvoiceNumber> <!--Fatura Numarası-->
                        <PlaceOfIncoterm>{{ $data['placeOfIncoterm'] }}</PlaceOfIncoterm>
                        <ExportLineItems>
                            <ExportLineItem>
                                <CommodityCode>{{ $data['commodityCode'] }}</CommodityCode><!--Urune ait GTIP kodu alanı-->
                                <ItemNumber>{{ $data['itemNumber'] }}</ItemNumber><!--Urune ait Seri Numarası-->
                                <Quantity>{{ $data['quantity'] }}</Quantity><!--Urun adet miktarı-->
                                <QuantityUnitOfMeasurement>{{ $data['quantityUnitOfMeasurement'] }}</QuantityUnitOfMeasurement> <!--Urun parça ise PCS kalmalıdır-->
                                <ItemDescription>{{ $data['itemDescription'] }}</ItemDescription> <!--Ürün açıklaması detaylı olarak ingilizce yazılmalıdır-->
                                <UnitPrice>{{ $data['unitPrice'] }}</UnitPrice> <!--Urune ait fiyat bilgisi-->
                                <NetWeight>{{ $data['netWeight'] }}</NetWeight><!-- Urunun net ağırlığı(kg olarak)-->
                                <GrossWeight>{{ $data['grossWeight'] }}</GrossWeight> <!--Urunun brüt ağırlığı-->
                                <ManufacturingCountryCode>{{ $data['manufacturingCountryCode'] }}</ManufacturingCountryCode>
                            </ExportLineItem>
                        </ExportLineItems>
                    </ExportDeclaration>
                </InternationalDetail>
                <Ship>
                    <Shipper> <!-- GÖnderici bilgilerinin girileceği alandır-->
                        <Contact> <!--Gonderici iletişim bilgileri-->
                            <PersonName>{{ $data['sPersonName'] }}</PersonName>
                            <CompanyName>{{ $data['sCompanyName'] }}</CompanyName>
                            <PhoneNumber>{{ $data['sPhoneNumber'] }}</PhoneNumber>
                            <EmailAddress>{{ $data['sEmailAddress'] }}</EmailAddress>
                        </Contact>
                        <Address> <!--Gönderici adres bilgileri-->
                            <StreetLines>{{ $data['sStreetLines'] }}</StreetLines>
                            {!! $data['sStreetName'] !!}
                            <City>{{ $data['sCity'] }}</City>
                            <PostalCode>{{ $data['sPostalCode'] }}</PostalCode>
                            <CountryCode>{{ $data['sCountryCode'] }}</CountryCode>
                        </Address>
                    </Shipper>
                    <Recipient> <!-- Alıcı iletişim bilgilerinin girileceği alandır-->
                        <Contact> <!-- Alıcı iletişim bilgileri-->
                            <PersonName>{{ $data['rPersonName'] }}</PersonName>
                            <CompanyName>{{ $data['rCompanyName'] }}</CompanyName>
                            <PhoneNumber>{{ $data['rPhoneNumber'] }}</PhoneNumber>
                            <EmailAddress>{{ $data['rEmailAddress'] }}</EmailAddress>
                        </Contact>
                        <Address> <!-- Alıcı adres bilgileri-->
                            <StreetLines>{{ $data['rStreetLines'] }}</StreetLines> 
                            <City>{{ $data['rCity'] }}</City>
                            <PostalCode>{{ $data['rPostalCode'] }}</PostalCode>
                            <CountryCode>{{ $data['rCountryCode'] }}</CountryCode>
                        </Address>
                    </Recipient>
                </Ship>
                <Packages>
                    <RequestedPackages number="1">
                        <InsuredValue>{{ $data['insuredValue'] }}</InsuredValue>
                        <Weight>{{ $data['weight'] }}</Weight>
                        <Dimensions>
                            <Length>{{ $data['length'] }}</Length>
                            <Width>{{ $data['width'] }}</Width>
                            <Height>{{ $data['height'] }}</Height>
                        </Dimensions>
                        <CustomerReferences>{{ $data['customerReferences'] }}</CustomerReferences>
                    </RequestedPackages>
                </Packages>
            </RequestedShipment>
        </ShipmentRequest>
    </s:Body>
</s:Envelope>