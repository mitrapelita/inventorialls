import json
with open(r'C:\Users\IT Inventory\.gemini\antigravity-ide\brain\e26dcce4-31e4-436a-bef7-186ec19b84fb\.system_generated\logs\transcript_full.jsonl', encoding='utf-8') as f:
    lines = f.readlines()

contents = []
for l in lines:
    try:
        d = json.loads(l)
        if d.get('content') and 'Total Lines: 785' in d['content'] and 'validasi.blade.php' in d['content']:
            contents.append(d)
    except Exception as e:
        pass

if contents:
    last = contents[-1]
    with open('recovered.json', 'w', encoding='utf-8') as out:
        json.dump(last, out, indent=2)

