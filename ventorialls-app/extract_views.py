import json
with open(r'C:\Users\IT Inventory\.gemini\antigravity-ide\brain\e26dcce4-31e4-436a-bef7-186ec19b84fb\.system_generated\logs\transcript_full.jsonl', encoding='utf-8') as f:
    lines = f.readlines()

contents = []
for l in lines:
    try:
        d = json.loads(l)
        if 'tool_calls' in d:
            for tc in d['tool_calls']:
                if tc['name'] == 'view_file' and 'validasi.blade.php' in tc.get('args', {}).get('AbsolutePath', ''):
                    contents.append(tc['args'])
    except Exception as e:
        pass

with open('views.json', 'w', encoding='utf-8') as out:
    json.dump(contents, out, indent=2)

