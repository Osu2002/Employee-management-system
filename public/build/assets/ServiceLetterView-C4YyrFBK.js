import{L as p,D as h,J as a,o as u,e as f,w as l,f as e,g as _,x as g,G as y}from"./index-QC1Cz5ZB.js";import{A as b}from"./AppLayout-C2fcH5kv.js";import"./html2pdf-C1PPJNF9.js";import{E as w}from"./Editor-i6n_uABq.js";import{b as v}from"./browser-CeceZ__e.js";import{_ as x}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./SideNavBar-Do_6nNue.js";const L={components:{Link:p,AppLayout:b,Editor:w},props:{errors:Object,employee:Object,employeeUrl:Object,service_letter_data:Object},data(){return{form:h({id:"",editorContent:"",employee_id:this.employee.id}),qrCodeData:"",qrCodeImageUrl:""}},computed:{formattedDate(){const o=new Date,t={year:"numeric",month:"long",day:"numeric"};return o.toLocaleDateString("en-US",t)}},mounted(){this.service_letter_data&&(this.form.id=this.employee.id,this.form.editorContent=this.service_letter_data.letter,this.employeeUrl?this.generateQRCode():console.error("Employee URL is missing, QR code cannot be generated."))},methods:{generateQRCode(){if(!this.employeeUrl){console.error("Cannot generate QR code: employeeUrl is missing or invalid.");return}try{const o=typeof this.employeeUrl=="object"?JSON.stringify(this.employeeUrl):this.employeeUrl;this.qrCodeData=o,v.toDataURL(o,{width:180,height:150}).then(t=>{this.qrCodeImageUrl=t,console.log("QR Code generated successfully.")}).catch(t=>{console.error("Error generating QR code:",t)})}catch(o){console.error("Error processing employeeUrl for QR code:",o)}},downloadCertificate(){const n=`<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><title>Document</title></head><body><style>
      .qr-code {
        position: absolute;
        top: 50px;
        bottom: 20px;
        left: 80%;
        width: 150px;
        text-align: right;
      }
      .qr-code img {
        width: 100%;
        max-width: 150px;
        height: auto;
      }
      .qr-code p {
        margin-top: 5px;
        font-size: 14px;
        color: #333;
      }
      .container {
        position: relative;
        width: 50%;
        height: 50%;
        padding: 20px;
        box-sizing: border-box;
      }
    </style>`+document.getElementById("certificate").innerHTML+"</body></html>",r=new Blob([n],{type:"application/msword"}),s=document.createElement("a");s.href=URL.createObjectURL(r),s.download="service_agreement_letter.doc",s.click(),URL.revokeObjectURL(s.href),console.log("Word document downloaded successfully")},submit(){this.form.post(this.ServiceLetter?route("employee.ServiceLetter.update"):route("employee.ServiceLetter.store"),{onSuccess:()=>{this.form.reset(),this.$root.showMessage("success",'<span class="text-success">Success</span><br/>',"A Record Was Created Successfully! ")},onError:()=>{this.form.reset("password","password_confirmation"),this.$root.showMessage("error",'<span class="text-danger">Error</span><br>',"Something went wrong! ")}})}}},C={class:"row"},U={class:"col-md-12"},R={class:"card mb-4"},S={class:"card-header pb-0"},E=e("h5",null,"Employee",-1),k=e("p",null,"Manage Employee Service Letter",-1),q=y("Edit"),D={id:"certificate",class:"container",style:{"background-image":"url(/public/backend/assets/images/service-letter-background.png)"}},Q={class:"content"},j=["innerHTML"],M={class:"document-container"},O=e("div",{class:"content"},null,-1),A={class:"qr-code"},B=["src"],T=e("h1",null,null,-1);function I(o,t,c,d,n,r){const s=a("Link"),m=a("AppLayout");return u(),f(m,null,{default:l(()=>[e("div",C,[e("div",U,[e("div",R,[e("div",S,[E,k,e("button",{type:"button",class:"btn btn-danger",onClick:t[0]||(t[0]=(...i)=>r.downloadCertificate&&r.downloadCertificate(...i))}," Download "),_(s,{type:"button",href:o.route("employee.service_letter",n.form.employee_id),class:"btn btn-outline-danger mx-2",style:{"margin-right":"10px"}},{default:l(()=>[q]),_:1},8,["href"]),e("form",{id:"formAccountSettings",onSubmit:t[1]||(t[1]=g((...i)=>r.submit&&r.submit(...i),["prevent"]))},[e("div",D,[e("div",Q,[e("div",null,[e("p",{innerHTML:c.service_letter_data.letter},null,8,j)])]),e("div",M,[O,e("div",A,[e("img",{src:n.qrCodeImageUrl,alt:"QR Code"},null,8,B)])])]),T],32)])])])])]),_:1})}const G=x(L,[["render",I]]);export{G as default};
