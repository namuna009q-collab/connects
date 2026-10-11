from flask import Flask, jsonify
app = Flask(__name__)

@app.get("/health")
def health():
    return jsonify({
        "ok": True,
        "message": "ready."
    })

if __name__ == "__main__":
    # Local-only helper service. Do not expose this development server directly to the internet.
    app.run(host="127.0.0.1", port=5001, debug=False)
