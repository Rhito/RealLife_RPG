import type { ReactNode } from "react";
import Block from "./components/Block";

function App() {
  const renderGrid = function (num: number): ReactNode {
    return <Block num={num} />;
  };
  return (
    <div className="wrapper">
      <h1 className="text-center my-[4rem] font-bold text-xl">
        Solve the quizzel!
      </h1>
      <div className="grid grid-cols-4 gap-4 w-[calc(12.5*5rem+1px)] mx-[auto] my-0">
        {renderGrid(1)}
      </div>
    </div>
  );
}

export default App;
