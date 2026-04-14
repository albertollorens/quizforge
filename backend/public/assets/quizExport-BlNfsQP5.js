function u(n){return{type:n,title:"",statement:"",answers:[]}}function o(n){switch(n){case"singlechoice":case"multichoice":return{text:"",correct:!1,weight:0};case"shortanswer":case"truefalse":return{text:"",correct:!1};case"matching":return{text:"",match_pair:""};default:return null}}function m(n){const t=u(n);return n==="essay"?(t.answers=[],t):(t.answers.push(o(n)),t.answers.push(o(n)),t)}function x(n){if(n.type==="essay")return;const t=o(n.type);n.answers.push(t)}function l(n){return{type:n.type,title:n.title||"",statement:n.statement||"",answers:n.answers?n.answers.map(t=>({...t})):[]}}const $={createQuestion:m,addAnswer:x,normalizeQuestionFromAPI:l};function h(n){return n.map(t=>{var a;const e=`::${t.title}::`;if(t.type==="singlechoice"){const r=t.answers.map(s=>s.correct?`=${s.text}`:`~${s.text}`).join(`
`);return`${e}[html]${t.statement} {
${r}
}`}if(t.type==="multichoice"){const r=t.answers.map(s=>`~%${s.weight}%${s.text}`).join(`
`);return`${e}[html]${t.statement} {
${r}
}`}if(t.type==="truefalse"){const r=((a=t.answers.find(s=>s.correct))==null?void 0:a.text.toUpperCase())||"FALSE";return`${e}[html]${t.statement} {${r}}`}if(t.type==="shortanswer"){const r=t.answers.map(s=>`=${s.text}`).join(" ");return`${e}[html]${t.statement} {${r}}`}if(t.type==="essay")return`${e}[html]${t.statement} {}`;if(t.type==="matching"){const r=t.answers.map(s=>`=${s.text}->${s.match_pair}`).join(`
`);return`${e}[html]${t.statement} {
${r}
}`}return""}).join(`

`)}function w(n){let t=`<?xml version="1.0" encoding="UTF-8"?>
<quiz>`;return n.forEach(e=>{const a=r=>`<![CDATA[${r}]]>`;switch(e.type){case"singlechoice":case"multichoice":t+=`
<question type="multichoice">
  <name><text>${e.title}</text></name>
  <questiontext format="html"><text>${a(e.statement)}</text></questiontext>
  <single>${e.type==="singlechoice"}</single>
`,e.answers.forEach(i=>{const c=e.type==="singlechoice"?i.correct?100:0:i.weight;t+=`  <answer fraction="${c}"><text>${a(i.text)}</text></answer>
`}),t+=`</question>
`;break;case"truefalse":const r=e.answers.find(i=>i.text.toLowerCase()==="true"),s=e.answers.find(i=>i.text.toLowerCase()==="false");t+=`
<question type="truefalse">
  <name><text>${e.title}</text></name>
  <questiontext format="html"><text>${a(e.statement)}</text></questiontext>
  <answer fraction="${r!=null&&r.correct?100:0}"><text>true</text></answer>
  <answer fraction="${s!=null&&s.correct?100:0}"><text>false</text></answer>
</question>
`;break;case"shortanswer":t+=`
<question type="shortanswer">
  <name><text>${e.title}</text></name>
  <questiontext format="html"><text>${a(e.statement)}</text></questiontext>
`,e.answers.forEach(i=>{t+=`<answer fraction="100"><text>${a(i.text)}</text></answer>
`}),t+=`</question>
`;break;case"essay":t+=`
<question type="essay">
  <name><text>${e.title}</text></name>
  <questiontext format="html"><text>${a(e.statement)}</text></questiontext>
</question>
`;break;case"matching":t+=`
<question type="matching">
  <name><text>${e.title}</text></name>
  <questiontext format="html"><text>${a(e.statement)}</text></questiontext>
`,e.answers.forEach(i=>{t+=`  <subquestion>
    <text>${a(i.text)}</text>
    <answer><text>${a(i.match_pair)}</text></answer>
  </subquestion>
`}),t+=`</question>
`;break}}),t+="</quiz>",t}export{$ as Q,w as a,m as c,h as g,l as n};
