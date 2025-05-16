import { Marked } from 'marked';
import { markedHighlight } from 'marked-highlight';
import hljs from 'highlight.js';

// or UMD script
// <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
// <script src="https://cdn.jsdelivr.net/npm/marked-highlight/lib/index.umd.js"></script>
// const { Marked } = globalThis.marked;
// const { markedHighlight } = globalThis.markedHighlight;
export const marked = new Marked(
	markedHighlight({
		emptyLangClass: 'hljs',
		langPrefix: 'hljs language-',
		highlight(code, lang, info) {
			const language = hljs.getLanguage(lang) ? lang : 'plaintext';
			return hljs.highlight(code, { language }).value;
		},
	}),
);

console.log(
	marked.parse(`
\`\`\`javascript
const highlight = "code";
\`\`\`
`),
);
// <pre><code class="hljs language-javascript">
//   <span class="hljs-keyword">const</span> highlight = <span class="hljs-string">&quot;code&quot;</span>;
// </code></pre>
