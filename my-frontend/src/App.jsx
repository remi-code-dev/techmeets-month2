import { useEffect, useState } from 'react'
import axios from 'axios'

function App() {
  const [posts, setPosts] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  // 第2引数が [] なので、最初の表示時に1回だけ実行される
  useEffect(() => {
    axios
      .get('http://localhost/api/posts')
      .then((res) => {
        // res.data       … axios が受け取ったレスポンス本体
        // res.data.data  … Laravel の Resource が包んだ配列
        setPosts(res.data.data)
      })
      .catch((err) => {
        console.error(err)
        setError('データの取得に失敗しました')
      })
      .finally(() => {
        setLoading(false)
      })
  }, [])

  if (loading) {
    return <p>読み込み中...</p>
  }

  if (error) {
    return <p>{error}</p>
  }

  return (
    <div>
      <h1>投稿一覧</h1>

      <ul>
        {posts.map((post) => (
          <li key={post.id}>
            <h2>{post.title}</h2>
            <p>
              投稿者：{post.user?.name ?? '不明'} ／ {post.created_at}
            </p>
            <p>{post.content}</p>
          </li>
        ))}
      </ul>
    </div>
  )
}

export default App
