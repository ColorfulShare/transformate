<!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
            
            <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
            <script type="text/javascript"  src="{{ asset('js/web3.min.js') }}" ></script> 
            <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.2.11/web3.min.js"></script>-->

            <script type="text/javascript"> 
                /*if (typeof window.ethereum !== 'undefined') {
                    console.log('MetaMask is installed!');
                } */  
                if (typeof window.web3 !== "undefined" && typeof window.web3.currentProvider !== "undefined"){ 
                    var web3 = new Web3 (window.web3.currentProvider); 
                }else{ 
                    var web3 = new Web3(); 
                } 
                
                $(function() {
                    /*const ethereumButton = document.querySelector('.enableEthereumButton');
                    ethereumButton.addEventListener('click', () => {
                        //Will Start the metamask extension
                        ethereum.request({ method: 'eth_requestAccounts' });
                    });*/

                    const ethereumButton = document.querySelector('.enableEthereumButton');
                    const registerButton = document.querySelector('.registerButton');
                    const showAccount = document.querySelector('.showAccount');

                    ethereumButton.addEventListener('click', () => {
                        getAccount();
                    });

                    registerButton.addEventListener('click', () => {
                        instanciarContrato();
                    });
                    
                    async function getAccount() {
                        const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
                        const account = accounts[0];
                        showAccount.innerHTML = account;
                        document.getElementById("address_account").value = account;
                    }
                    
                    function instanciarContrato(){
                        $.getJSON('http://api.etherscan.io/api?module=contract&action=getabi&address=0x655F5c61dd3382DB62139b360BA3C9Eda0f95F98&apikey=JEAYAMGKECBZFSX4GIKXC6DFAGUSR4716T', function (data) {
                            var contractABI = "";
                            contractABI = JSON.parse(data.result); 
                            
                            var Contrato = new web3.eth.Contract(contractABI, '0x655F5c61dd3382DB62139b360BA3C9Eda0f95F98', {
                                from: document.getElementById("address_account").value // default from address
                                //gasPrice: '20000000000' // default gas price in wei, 20 gwei in this case
                            });
                            //console.log(Contrato);
                        
                            var owner = Contrato.methods.owner.call();
                            //var registro = Contrato.methods.registrationExt(document.getElementById("address_account").value).send();
                            console.log(owner);
                        });
                    }

                    function registrar(){
                        
                    }
                });

                
                
                
            </script> 

        </head> 
        <body>
            <button class="enableEthereumButton">Enable Ethereum</button>
            <h2>Account: <span class="showAccount"></span></h2>
            <input type="text" id="address_account">

            <button class="registerButton">Registrarme</button>

            
        </body>
</html>

