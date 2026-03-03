// src/helpers/quizExport.js
export function generateGIFT(questions) {
  return questions.map((q) => {
    const title = `::${q.title}::`

    // SINGLECHOICE
    if (q.type === 'singlechoice') {
      const answers = q.answers.map(a =>
        a.correct ? `=${a.text}` : `~${a.text}`
      ).join('\n')
      return `${title}[html]${q.statement} {\n${answers}\n}`
    }

    // MULTICHOICE
    if (q.type === 'multichoice') {
      const answers = q.answers.map(a => `~%${a.weight}%${a.text}`).join('\n')
      return `${title}[html]${q.statement} {\n${answers}\n}`
    }

    // TRUE/FALSE
    if (q.type === 'truefalse') {
      const correctAnswer = q.answers.find(a => a.correct)?.text.toUpperCase() || 'FALSE'
      return `${title}[html]${q.statement} {${correctAnswer}}`
    }

    // SHORT ANSWER
    if (q.type === 'shortanswer') {
      const answers = q.answers.map(a => `=${a.text}`).join(' ')
      return `${title}[html]${q.statement} {${answers}}`
    }

    // ESSAY
    if (q.type === 'essay') {
      return `${title}[html]${q.statement} {}`
    }

    // MATCHING
    if (q.type === 'matching') {
      const answers = q.answers.map(a => `=${a.text}->${a.match_pair}`).join('\n')
      return `${title}[html]${q.statement} {\n${answers}\n}`
    }

    return ''
  }).join('\n\n')
}


export function generateXML(questions) {
  let xml = `<?xml version="1.0" encoding="UTF-8"?>\n<quiz>`
  
  questions.forEach((q) => {
    const esc = (str) => `<![CDATA[${str}]]>`

    switch(q.type) {
      case 'singlechoice':
      case 'multichoice':
        xml += `
<question type="multichoice">
  <name><text>${q.title}</text></name>
  <questiontext format="html"><text>${esc(q.statement)}</text></questiontext>
  <single>${q.type === 'singlechoice'}</single>
`
        q.answers.forEach(a => {
          const fraction = q.type === 'singlechoice' ? (a.correct ? 100 : 0) : a.weight
          xml += `  <answer fraction="${fraction}"><text>${esc(a.text)}</text></answer>\n`
        })
        xml += `</question>\n`
        break

      case 'truefalse':
        const trueAns = q.answers.find(a => a.text.toLowerCase() === 'true')
        const falseAns = q.answers.find(a => a.text.toLowerCase() === 'false')
        xml += `
<question type="truefalse">
  <name><text>${q.title}</text></name>
  <questiontext format="html"><text>${esc(q.statement)}</text></questiontext>
  <answer fraction="${trueAns?.correct ? 100 : 0}"><text>true</text></answer>
  <answer fraction="${falseAns?.correct ? 100 : 0}"><text>false</text></answer>
</question>\n`
        break

      case 'shortanswer':
        xml += `
<question type="shortanswer">
  <name><text>${q.title}</text></name>
  <questiontext format="html"><text>${esc(q.statement)}</text></questiontext>\n`
        q.answers.forEach(a => {
          xml += `<answer fraction="100"><text>${esc(a.text)}</text></answer>\n`
        })
        xml += `</question>\n`
        break

      case 'essay':
        xml += `
<question type="essay">
  <name><text>${q.title}</text></name>
  <questiontext format="html"><text>${esc(q.statement)}</text></questiontext>
</question>\n`
        break

      case 'matching':
        xml += `
<question type="matching">
  <name><text>${q.title}</text></name>
  <questiontext format="html"><text>${esc(q.statement)}</text></questiontext>\n`
        q.answers.forEach(a => {
          xml += `  <subquestion>
    <text>${esc(a.text)}</text>
    <answer><text>${esc(a.match_pair)}</text></answer>
  </subquestion>\n`
        })
        xml += `</question>\n`
        break
    }
  })

  xml += `</quiz>`
  return xml
}