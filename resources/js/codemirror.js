import {basicSetup, EditorView} from "codemirror";
import {json} from "@codemirror/lang-json";
import {oneDark} from "@codemirror/theme-one-dark";

export default ({
    create (element, config) {
        let extensions = [
            basicSetup,
            json(),
        ];
        if (config.oneDark) {
            extensions.push(oneDark);
        }
        return new EditorView({
            doc: config.doc,
            extensions: extensions,
            parent: element,
        });
    },
});
