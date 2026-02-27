import re
import sys
import json
import pickle
import math

#Argument check
if len(sys.argv) != 4 :
	print ("\n\nPenggunaan\n\tquery.py [index] [n] [query]..\n")
	sys.exit(1)

query = sys.argv[3].split(" ")
n = int(sys.argv[2])

#load and read index file with pickle module
with open(sys.argv[1], 'rb') as indexdb:
	indexFile = pickle.load(indexdb)

# ========================================
# Cosine Similarity Calculation
# ========================================

# Step 1: Build FULL document vectors from the entire index
# full_doc_vectors[url] = { 'info': {...}, 'tfidf': {term: score} }
# This contains ALL terms for each document, not just query terms
full_doc_vectors = {}
for term, docs in indexFile.items():
	for doc in docs:
		url = doc['url']
		if url not in full_doc_vectors:
			full_doc_vectors[url] = {
				'info': {
					'url': doc['url'],
					'title': doc['title'],
					'image': doc['image'],
					'price': doc['price']
				},
				'tfidf': {}
			}
		full_doc_vectors[url]['tfidf'][term] = doc['score']

# Step 2: Count term frequency in the query
query_tf = {}
for q in query:
	q = q.lower()
	query_tf[q] = query_tf.get(q, 0) + 1

# Step 3: Find candidate documents (yang mengandung minimal 1 query term)
candidate_urls = set()
for q in query_tf:
	if q not in indexFile:
		continue
	for doc in indexFile[q]:
		candidate_urls.add(doc['url'])

# Step 4: Build query TF-IDF vector
# Use min score (= IDF, karena TF minimum = 1) agar query vector proporsional
# dengan document vector saat exact match → cosine similarity = 1.0
query_vector = {}
for q in query_tf:
	if q not in indexFile:
		continue
	# min score = IDF (score saat TF=1), bukan avg yang terdistorsi oleh TF>1
	idf_score = min(d['score'] for d in indexFile[q])
	query_vector[q] = query_tf[q] * idf_score

# Step 5: Calculate cosine similarity using FULL document vectors
results = []
query_mag = math.sqrt(sum(v ** 2 for v in query_vector.values()))

if query_mag > 0:
	for url in candidate_urls:
		if url not in full_doc_vectors:
			continue
		doc_data = full_doc_vectors[url]
		doc_tfidf = doc_data['tfidf']  # SEMUA term dalam dokumen ini

		# Dot product: hanya term yang ada di query DAN dokumen
		dot_product = sum(query_vector.get(t, 0) * doc_tfidf.get(t, 0) for t in query_vector)

		# Document magnitude: dihitung dari SEMUA term dokumen (bukan hanya query terms)
		# Inilah yang membuat cosine similarity selalu < 1.0
		doc_mag = math.sqrt(sum(v ** 2 for v in doc_tfidf.values()))

		if doc_mag > 0:
			cosine_sim = dot_product / (query_mag * doc_mag)
		else:
			cosine_sim = 0.0

		# Clamp: pastikan skor tidak melebihi 1.0
		cosine_sim = min(cosine_sim, 1.0)

		result = doc_data['info'].copy()
		result['score'] = round(cosine_sim, 6)
		results.append(result)

# Step 6: Sort by cosine similarity descending and output top-n
count = 1
for data in sorted(results, key=lambda k: k['score'], reverse=True):
	print(json.dumps(data))
	if count == n:
		break
	count += 1