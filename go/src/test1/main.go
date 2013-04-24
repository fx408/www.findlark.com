package main

import (
	"fmt"
	"math"
	"runtime"
	"time"
)

func sum(start, end float64, c chan float64) {
	pi := 0.0
	k := 1

	for i := start; i < end; i++ {
		k = int(i)%2 + 1
		pi = pi + math.Pow(-1, float64(k))/(2*i-1.0)
	}

	c <- pi
}

func main() {

	runtime.GOMAXPROCS(2)
	fmt.Println("START: ", time.Now())
	c := make(chan float64)
	pi := 0.0
	m := 500000000.0
	n := 3.0

	for i := 1.0; i < n; i++ { // 一共25 亿次
		fmt.Println((i-1.0)*m+1.0, "--", i*m)
		go sum((i-1.0)*m+1.0, i*m, c)
	}

	for i := 1.0; i < n; i++ {
		pi = pi + <-c
	}

	fmt.Println(4 * pi)
	fmt.Println("END: ", time.Now())
}
