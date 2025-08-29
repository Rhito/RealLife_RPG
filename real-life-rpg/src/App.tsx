import { useState } from "react";
import "./App.css";

function App() {
  const [todoList, setTodoList] = useState<string[]>([]);
  const [text, setText] = useState<string>("");

  const renderTodo = todoList?.map((todo, i) => {
    return (
      <div key={i}>
        <h3>{todo}</h3>
      </div>
    );
  });

  const onSubmited = () => {
    if (!text.trim()) return;
    setTodoList([text, ...todoList]);
    setText("");
  };
  console.log(todoList);
  return (
    <>
      <div>
        <input
          type="text"
          value={text}
          onChange={(e) => {
            setText(e.target.value);
          }}
        />
        <button type="button" onClick={onSubmited}>
          them
        </button>
      </div>
      <div>{renderTodo}</div>
    </>
  );
}

export default App;
